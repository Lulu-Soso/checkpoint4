<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
}
