<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        // si la session Cart n'existe pas -> envoie un tableau vide
        $cart = $this->session->get('cart', []);

        // si un produit existe dans le panier alors tu rajoutes ++
        // Si ce n'est pas le cas, alors rajoute simplement 1 quantité au panier
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function getFull()
    {
        // panier complet avec un tableau vide
        $cartComplete = [];

        // On met la condition pour eviter un message d'erreur en cas de panier vide
        if ($this->get()) {
            // a chaque produit, je voudrais enrichir ce $cartComplete de data de mon produit que Doctrine va aller chercher en BDD
            // qu'il injecte dans $cartComplete une nouvelle entrée et que cette nouvelle entrée ait le produit complet la quantité
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                // Si ce $product_object n'existe pas, dans ce cas-là, supprime le produit du panier
                if (!$product_object) {
                    $this->delete($id);
                    continue; // veut dire "sors de cette boucle foreach et passe au produit suivant sans affecter $cartComplete, ni retourner"
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}
