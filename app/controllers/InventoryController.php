<?php
declare(strict_types=1);

final class InventoryController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $stock = Inventory::stockByIngredient();
    $recent = Inventory::recent(80);
    $ingredients = Ingredient::all();
    $this->render('inventory/index', [
      'stock' => $stock,
      'recent' => $recent,
      'ingredients' => $ingredients
    ]);
  }

  public function add(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }

    $data = [
      'ingredient_id' => $this->post('ingredient_id'),
      'txn_type' => $this->post('txn_type'),
      'quantity' => $this->post('quantity'),
      'note' => trim((string)$this->post('note')),
      'txn_date' => $this->post('txn_date') ?: date('Y-m-d'),
    ];

    if (!in_array($data['txn_type'], ['IN', 'OUT', 'ADJUST'], true)) {
      $this->redirect('inventory/index');
    }

    Inventory::addTxn($data);
    $this->redirect('inventory/index');
  }
}
