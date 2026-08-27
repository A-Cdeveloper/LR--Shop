<?php

return [

    'common' => [
        'not_found' => 'Nije pronađeno.',
        'unauthorized' => 'Neovlašćeno.',
        'forbidden' => 'Nemate dozvolu za pristup ovom resursu.',
    ],

    'auth' => [
        'registered' => 'Registrovani ste. Proverite email da aktivirate nalog. Proverite i spam folder.',
        'email_verified' => 'Email je verifikovan.',
        'invalid_verification_link' => 'Nevažeći link za verifikaciju.',
        'invalid_credentials' => 'Neispravni podaci za prijavu.',
        'verify_email_first' => 'Prvo verifikujte email adresu.',
        'account_inactive' => 'Vaš nalog nije aktivan. Kontaktirajte podršku.',
        'logged_out' => 'Odjavljeni ste.',
        'profile_updated' => 'Profil je uspešno ažuriran.',
        'admin_cannot_delete' => 'Admin nalog ne može biti obrisan.',
        'password_changed' => 'Lozinka je uspešno promenjena.',
        'current_password_incorrect' => 'Trenutna lozinka nije ispravna.',
        'forgot_password_sent' => 'Ako taj email postoji, poslali smo link za reset lozinke.',
        'reset_token_invalid' => 'Token za reset lozinke nije važeći.',
        'password_reset' => 'Lozinka je uspešno resetovana.',
        'verification_resent' => 'Ako taj email postoji i nije verifikovan, poslali smo link za verifikaciju.',
    ],

    'cart' => [
        'not_found' => 'Korpa nije pronađena.',
        'item_not_found' => 'Stavka korpe nije pronađena.',
        'not_enough_stock' => 'Nema dovoljno na stanju.',
        'not_enough_stock_for_product' => 'Nema dovoljno na stanju za ovaj proizvod.',
        'product_not_active' => 'Proizvod nije aktivan.',
    ],

    'orders' => [
        'cart_empty' => 'Korpa je prazna.',
        'placed' => 'Porudžbina je uspešno kreirana.',
        'invalid_status' => 'Nevažeći status.',
        'status_updated' => 'Status porudžbine je uspešno ažuriran.',
        'invalid_delivery_method' => 'Nevažeći ili neaktivan način dostave.',
        'invalid_payment_method' => 'Nevažeći ili neaktivan način plaćanja.',
        'payment_failed' => 'Plaćanje nije moglo da se pokrene. Pokušajte ponovo.',
        'refunded' => 'Porudžbina je uspešno refundirana.',
        'refund_not_allowed' => 'Samo plaćene Stripe porudžbine mogu biti refundirane.',
        'refund_failed' => 'Refundiranje nije uspelo. Pokušajte ponovo.',
    ],

    'admin' => [
        'category_created' => 'Kategorija je uspešno kreirana.',
        'category_updated' => 'Kategorija je uspešno ažurirana.',
        'category_delete_has_products' => 'Ne možete obrisati kategoriju koja ima proizvode.',
        'product_created' => 'Proizvod je uspešno kreiran.',
        'product_updated' => 'Proizvod je uspešno ažuriran.',
        'user_updated' => 'Korisnik je uspešno ažuriran.',
        'cannot_change_own_active_status' => 'Ne možete menjati sopstveni status aktivnosti.',
        'delivery_method_created' => 'Način dostave je uspešno kreiran.',
        'delivery_method_updated' => 'Način dostave je uspešno ažuriran.',
        'payment_method_created' => 'Način plaćanja je uspešno kreiran.',
        'payment_method_updated' => 'Način plaćanja je uspešno ažuriran.',
        'tax_created' => 'Porez je uspešno kreiran.',
        'tax_updated' => 'Porez je uspešno ažuriran.',
        'tax_delete_default' => 'Ne možete obrisati podrazumevani porez.',
        'tax_delete_has_products' => 'Ne možete obrisati porez koji je vezan za proizvode.',
    ],

];
