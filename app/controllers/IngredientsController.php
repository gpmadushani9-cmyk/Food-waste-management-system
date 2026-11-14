<?php
declare(strict_types=1);

final class IngredientsController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $items = Ingredient::all();
    $this->render('ingredients/list', ['items' => $items]);
  }

  public function create(): void {
    Auth::requireLogin();
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) $error = "Security check failed.";
      else {
        $data = [
          'name' => trim((string)$this->post('name')),
          'unit' => trim((string)$this->post('unit')),
          'cost_per_unit' => (string)$this->post('cost_per_unit'),
          'reorder_level' => (string)$this->post('reorder_level'),
        ];
        if ($data['name'] === '' || $data['unit'] === '') $error = "Name and unit are required.";
        else {
          Ingredient::create($data);
          $this->redirect('ingredients/index');
        }
      }
    }

    $this->render('ingredients/form', ['mode' => 'create', 'error' => $error, 'item' => null]);
  }

  public function edit(): void {
    Auth::requireLogin();
    $id = (int)$this->get('id', 0);
    $item = Ingredient::find($id);
    if (!$item) { http_response_code(404); echo "Not found"; exit; }

    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) $error = "Security check failed.";
      else {
        $data = [
          'name' => trim((string)$this->post('name')),
          'unit' => trim((string)$this->post('unit')),
          'cost_per_unit' => (string)$this->post('cost_per_unit'),
          'reorder_level' => (string)$this->post('reorder_level'),
        ];
        Ingredient::update($id, $data);
        $this->redirect('ingredients/index');
      }
    }

    $this->render('ingredients/form', ['mode' => 'edit', 'error' => $error, 'item' => $item]);
  }

  public function delete(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }
    $id = (int)$this->post('id', 0);
    Ingredient::delete($id);
    $this->redirect('ingredients/index');
  }
}
