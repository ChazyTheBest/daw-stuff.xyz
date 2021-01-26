<?php

namespace models;

//use framework\UserSession;
use framework\ActiveRecord;

/**
 * Cart Model.
 */
final class Cart// extends ActiveRecord
{
    // adds or removes by allowing negative numbers
    public function addItem(int $id, int $quantity): void
    {
        if (1===2/*UserSession::getInstance()->isLoggedIn()*/)
        {
            // TODO: add to db
            // missing user_cart entity
        }

        else
        {
            $cookie = $_COOKIE['items'][$id] ?? false;
            if ($cookie && ($cookie + $quantity) > 0)
                $quantity += $cookie;

            setcookie("items[$id]", $quantity, time() + 3600, '/');  /* expire in 1 hour */
        }
    }

    public function setItemQuantity(int $id, int $quantity): void
    {
        if (1===2/*UserSession::getInstance()->isLoggedIn()*/)
        {
            // TODO: add to db
            // missing user_cart entity
        }

        else
        {
            $cookie = $_COOKIE['items'][$id] ?? false;
            if ($cookie && $quantity > 0)
                setcookie("items[$id]", $quantity, time() + 3600, '/');  /* expire in 1 hour */
        }
    }

    public function deleteItem(int $id): void
    {
        if (1===2/*UserSession::getInstance()->isLoggedIn()*/)
        {
            // TODO: remove from db
            // missing user_cart entity
        }

        else
        {
            if (!isset($_COOKIE['items'][$id]))
                return;

            //unset($_COOKIE['items'][$id]);
            setcookie("items[$id]", '', time() - 3600, '/');
        }
    }

    public function getItemCount(): int
    {
        $count = 0;

        if (1===2/*UserSession::getInstance()->isLoggedIn()*/)
        {
            // TODO: get from db
            // missing user_cart entity
        }

        else
        {
            $count = isset($_COOKIE['items']) ? array_sum($_COOKIE['items']) : 0;
        }

        return $count;
    }

    public function moveCartToDB()
    {
        // todo
    }

    public function getCartItems(): array
    {
        if (!isset($_COOKIE['items']))
            return [];

        $ids = [];

        foreach ($_COOKIE['items'] as $key => $val)
            $ids[] = $key;

        return Product::findManyById($ids);
    }

    public function processCart(): void
    {
        if (isset($_COOKIE['items']))
            $this->emptyCart();
    }

    private function emptyCart(): void
    {
        foreach ($_COOKIE['items'] as $key => $val)
            setcookie("items[$key]", '', time() - 3600, '/');
    }
}
