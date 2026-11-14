<?php
declare(strict_types=1);

final class WasteController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $ingredients = Ingredient::all();
    $menu = MenuItem::all();
    $recent = Waste::recent(80);
    $this->render('waste/index', [
      'ingredients' => $ingredients,
      'menu' => $menu,
      'recent' => $recent
    ]);
  }

  public function add(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }

    $wasteDate = (string)($this->post('waste_date') ?: date('Y-m-d'));
    $ingredientId = $this->post('ingredient_id', '');
    $menuItemId = $this->post('menu_item_id', '');
    $wasteType = (string)$this->post('waste_type', 'overproduction');
    $qty = (float)$this->post('quantity', 0);

    // cost auto-calc: if ingredient selected => qty * cost_per_unit; if menu selected => qty * recipeCost
    $cost = 0.0;
    if ($ingredientId !== '') {
      $ing = Ingredient::find((int)$ingredientId);
      if ($ing) $cost = $qty * (float)$ing['cost_per_unit'];
    } elseif ($menuItemId !== '') {
      $cost = $qty * Recipe::recipeCostForMenuItem((int)$menuItemId);
    }

    Waste::add([
      'waste_date' => $wasteDate,
      'ingredient_id' => $ingredientId,
      'menu_item_id' => $menuItemId,
      'waste_type' => $wasteType,
      'quantity' => $qty,
      'cost' => $cost,
      'note' => trim((string)$this->post('note')),
    ]);

    $this->redirect('waste/index');
  }
}
