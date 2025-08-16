<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class MX_Loader extends CI_Loader
{
    protected $_module;
    public $_ci_plugins = array();
    public $_ci_cached_vars = array();

    public function initialize($controller = NULL)
    {
        $this->_module = CI::$APP->router->fetch_module();
        if ($controller instanceof MX_Controller) {
            $this->controller = $controller;
            foreach (get_class_vars('CI_Loader') as $var => $val) {
                if ($var != '_ci_ob_level') {
                    $this->$var = &CI::$APP->load->$var;
                }
            }
        } else {
            parent::initialize();
            $this->_autoloader(array());
        }
        $this->_add_module_paths($this->_module);
    }

    public function _add_module_paths($module = '')
    {
        if (empty($module)) return;
        foreach (Modules::$locations as $location => $offset) {
            if (is_dir($module_path = $location.$module.'/') && !in_array($module_path, $this->_ci_model_paths)) {
                array_unshift($this->_ci_model_paths, $module_path);
            }
        }
    }

    public function library($library, $params = NULL, $object_name = NULL)
    {
        if (is_array($library)) return $this->libraries($library);
        $class = strtolower(basename($library));
        if (isset($this->_ci_classes[$class]) && $_alias = $this->_ci_classes[$class]) return $this;

        $_alias = ($object_name !== null && $object_name !== '')
            ? strtolower((string)$object_name)
            : $class;

        list($path, $_library) = Modules::find($library, $this->_module, 'libraries/');
        if ($params === NULL) {
            list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');
            ($path2) && $params = Modules::load_file($file, $path2, 'config');
        }
        if ($path === FALSE) {
            return parent::library($library, $params, $object_name);
        } else {
            Modules::load_file($_library, $path);
            $library = ucfirst($_library);
            CI::$APP->$_alias = new $library($params);
            $this->_ci_classes[$class] = $_alias;
        }
        return $this;
    }
}
