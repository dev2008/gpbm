<?php
$key = sodium_crypto_aead_xchacha20poly1305_ietf_keygen();
echo bin2hex($key);