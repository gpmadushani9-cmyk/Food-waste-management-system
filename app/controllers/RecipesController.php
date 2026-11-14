<?php
declare(strict_types=1);

final class RecipesController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $menu = MenuItem::all();
    $ingredients = Ingredient::all();
    $menuId = (int)$this->get('menu_item_id', $menu[0]['id'] ?? 0);
    $rows = $menuId ? Recipe::rowsForMenuItem($menuId) : [];
    $recipeCost = $menuId ? Recipe::recipeCostForMenuItem($menuId) : 0.0;

    $this->render('recipes/list', [
      'menu' => $menu,
      'ingredients' => $ingredients,
      'menuId' => $menuId,
      'rows' => $rows,
      'recipeCost' => $recipeCost
    ]);
  }

  public function upsert(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }

    $menuId = (int)$this->post('menu_item_id', 0);
    $ingredientId = (int)$this->post('ingredient_id', 0);
    $qty = (float)$this->post('qty_per_item', 0);
    if ($menuId && $ingredientId && $qty >= 0) {
      Recipe::upsert($menuId, $ingredientId, $qty);
    }
    $this->redirect('recipes/index&menu_item_id=' . $menuId);
  }

  public function delete(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }

    $menuId = (int)$this->post('menu_item_id', 0);
    $ingredientId = (int)$this->post('ingredient_id', 0);
    if ($menuId && $ingredientId) {
      Recipe::delete($menuId, $ingredientId);
    }
    $this->redirect('recipes/index&menu_item_id=' . $menuId);
  }
}
