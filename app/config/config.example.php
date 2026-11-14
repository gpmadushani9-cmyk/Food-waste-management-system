<?php
// Copy this file to config.php and update values.

return [
  'app_name' => 'Food Waste & Profit Optimizer',
  'base_url' => '/food-waste-profit-optimizer/public', // change if hosted elsewhere

  'db' => [
    'host' => '127.0.0.1',
    'name' => 'food_waste_opt',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
  ],

  'security' => [
    'session_name' => 'fwpo_session',
    'csrf_key' => 'fwpo_csrf_token'
  ]
];
