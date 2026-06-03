<?php

echo password_verify(
    'admin123',
    '$2y$10$28JEEfpvPZsaKIp60.QkE.KQBBIlz8UVQdE54r05y8ErWxnRKNwT2'
) ? 'OK' : 'NO';