<?php
require_once LIBS . '/IController.php';

abstract class AController implements IController {
  private function getControllerName() {
    return get_class($this);
  }

  public function getModuleName() {
    $name = $this->getControllerName();

    if (substr($name, -strlen('Controller')) == 'Controller') {
      $name = substr($name, 0, -strlen('Controller'));
    }

    return strtolower($name);
  }

  private function getFilePath($viewname) {
    if (!preg_match("#^[0-9a-zA-Z_-]+$#", $viewname))
      throw new Exception("view name incorrect ($viewname), exiting!");

    $views_dir = MODULES . '/' . $this->getModuleName() . '/views';
    $viewpath = $views_dir . '/' . $viewname . '.php';
    if (!file_exists($viewpath))
      $viewpath = VIEWS . '/' . $viewname . '.php';

    if (!file_exists($viewpath))
      $viewpath = '';

    if (empty($viewpath))
      throw new Exception("view not found ($view), exiting!");

    return $viewpath;
  }

  public function render($viewname, $variables = array()) {
    try {
      $viewpath = $this->getFilePath($viewname);
      // We extract $variables so that the view can use it to render any data.
      extract($variables, EXTR_SKIP); // Extract the variables to a local namespace
      //ob_start(); // Start output buffering
      include $viewpath; // Include the template file
      //return ob_get_clean(); // End buffering and return its contents
    } catch (Exception $e) {
      error($e->getMessage());
      not_found();      
    }
  }
}
