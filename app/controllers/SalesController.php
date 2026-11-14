<?php
declare(strict_types=1);

final class SalesController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $menu = MenuItem::all();
    $recent = Sale::recent(80);
    $this->render('sales/index', [
      'menu' => $menu,
      'recent' => $recent
    ]);
  }

  public function add(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }

    $menuItemId = (int)$this->post('menu_item_id', 0);
    $qty = (float)$this->post('quantity', 0);
    $date = (string)($this->post('sale_date') ?: date('Y-m-d'));

    $menuItem = MenuItem::find($menuItemId);
    if (!$menuItem || $qty <= 0) $this->redirect('sales/index');

    $revenue = $qty * (float)$menuItem['price'];
    Sale::add([
      'sale_date' => $date,
      'menu_item_id' => $menuItemId,
      'quantity' => $qty,
      'revenue' => $revenue
    ]);

    $this->redirect('sales/index');
  }
}
