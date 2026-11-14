<?php
declare(strict_types=1);

final class MenuController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $items = MenuItem::all();
    $this->render('menu/list', ['items' => $items]);
  }

  public function create(): void {
    Auth::requireLogin();
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) $error = "Security check failed.";
      else {
        $data = [
          'name' => trim((string)$this->post('name')),
          'price' => (string)$this->post('price'),
          'active' => $this->post('active'),
        ];
        if ($data['name'] === '') $error = "Name is required.";
        else {
          MenuItem::create($data);
          $this->redirect('menu/index');
        }
      }
    }

    $this->render('menu/form', ['mode' => 'create', 'error' => $error, 'item' => null]);
  }

  public function edit(): void {
    Auth::requireLogin();
    $id = (int)$this->get('id', 0);
    $item = MenuItem::find($id);
    if (!$item) { http_response_code(404); echo "Not found"; exit; }

    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) $error = "Security check failed.";
      else {
        $data = [
          'name' => trim((string)$this->post('name')),
          'price' => (string)$this->post('price'),
          'active' => $this->post('active'),
        ];
        MenuItem::update($id, $data);
        $this->redirect('menu/index');
      }
    }

    $this->render('menu/form', ['mode' => 'edit', 'error' => $error, 'item' => $item]);
  }

  public function delete(): void {
    Auth::requireLogin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    if (!CSRF::verify($this->post('csrf'))) { http_response_code(403); exit; }
    $id = (int)$this->post('id', 0);
    MenuItem::delete($id);
    $this->redirect('menu/index');
  }
}
