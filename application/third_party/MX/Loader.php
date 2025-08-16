<?php (defined('BASEPATH')) or exit('No direct script access allowed');

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
            if (is_dir($module_path = $location . $module . '/') && !in_array($module_path, $this->_ci_model_paths)) {
                array_unshift($this->_ci_model_paths, $module_path);
            }
        }
    }

    public function config($file, $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        return CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
    }

    public function database($params = '', $return = FALSE, $query_builder = NULL)
    {
        if (
            $return === FALSE && $query_builder === NULL &&
            isset(CI::$APP->db) && is_object(CI::$APP->db) && !empty(CI::$APP->db->conn_id)
        ) {
            return FALSE;
        }
        require_once BASEPATH . 'database/DB.php';
        if ($return === TRUE) return DB($params, $query_builder);
        CI::$APP->db = DB($params, $query_builder);
        return $this;
    }

    public function helper($helper = array())
    {
        if (is_array($helper)) return $this->helpers($helper);
        if (isset($this->_ci_helpers[$helper])) return;
        list($path, $_helper) = Modules::find($helper . '_helper', $this->_module, 'helpers/');
        if ($path === FALSE) return parent::helper($helper);
        Modules::load_file($_helper, $path);
        $this->_ci_helpers[$_helper] = TRUE;
        return $this;
    }

    public function helpers($helpers = array())
    {
        foreach ($helpers as $_helper) $this->helper($_helper);
        return $this;
    }

    public function language($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
    {
        CI::$APP->lang->load($langfile, $idiom, $return, $add_suffix, $alt_path, $this->_module);
        return $this;
    }

    public function languages($languages)
    {
        foreach ($languages as $_language) $this->language($_language);
        return $this;
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
            // Fallback ke loader CI3 supaya core library seperti Session bisa diload
            return parent::library($library, $params, $object_name);
        } else {
            Modules::load_file($_library, $path);
            $library = ucfirst($_library);
            CI::$APP->$_alias = new $library($params);
            $this->_ci_classes[$class] = $_alias;
        }
        return $this;
    }

    public function libraries($libraries)
    {
        foreach ($libraries as $library => $alias) {
            (is_int($library)) ? $this->library($alias) : $this->library($library, NULL, $alias);
        }
        return $this;
    }

    public function model($model, $object_name = NULL, $connect = FALSE)
    {
        if (is_array($model)) return $this->models($model);
        $_alias = ($object_name !== null && $object_name !== '')
            ? $object_name
            : basename($model);

        if (in_array($_alias, $this->_ci_models, TRUE)) return $this;

        list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
        if ($path == FALSE) {
            parent::model($model, $object_name, $connect);
        } else {
            class_exists('CI_Model', FALSE) or load_class('Model', 'core');
            if ($connect !== FALSE && !class_exists('CI_DB', FALSE)) {
                if ($connect === TRUE) $connect = '';
                $this->database($connect, FALSE, TRUE);
            }
            Modules::load_file($_model, $path);
            $model = ucfirst($_model);
            CI::$APP->$_alias = new $model();
            $this->_ci_models[] = $_alias;
        }
        return $this;
    }

    public function models($models)
    {
        foreach ($models as $model => $alias) {
            (is_int($model)) ? $this->model($alias) : $this->model($model, $alias);
        }
        return $this;
    }

    public function module($module, $params = NULL)
    {
        if (is_array($module)) return $this->modules($module);
        $_alias = strtolower(basename($module));
        CI::$APP->$_alias = Modules::load(array($module => $params));
        return $this;
    }

    public function modules($modules)
    {
        foreach ($modules as $_module) $this->module($_module);
        return $this;
    }

    public function plugin($plugin)
    {
        if (is_array($plugin)) return $this->plugins($plugin);
        if (isset($this->_ci_plugins[$plugin])) return $this;
        list($path, $_plugin) = Modules::find($plugin . '_pi', $this->_module, 'plugins/');
        if ($path === FALSE && !is_file($_plugin = APPPATH . 'plugins/' . $_plugin . '.php')) {
            show_error("Unable to locate the plugin file: {$_plugin}");
        }
        Modules::load_file($_plugin, $path);
        $this->_ci_plugins[$plugin] = TRUE;
        return $this;
    }

    public function plugins($plugins)
    {
        foreach ($plugins as $_plugin) $this->plugin($_plugin);
        return $this;
    }

    public function view($view, $vars = array(), $return = FALSE)
    {
        list($path, $_view) = Modules::find($view, $this->_module, 'views/');
        if ($path != FALSE) {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }
        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));
    }

    protected function &_ci_get_component($component)
    {
        return CI::$APP->$component;
    }

    public function __get($class)
    {
        return (isset($this->controller)) ? $this->controller->$class : CI::$APP->$class;
    }

    public function _ci_load($_ci_data)
    {
        extract($_ci_data);
        if (isset($_ci_view)) {
            $_ci_path = '';
            $_ci_file = (pathinfo($_ci_view, PATHINFO_EXTENSION)) ? $_ci_view : $_ci_view . '.php';
            foreach ($this->_ci_view_paths as $path => $cascade) {
                if (file_exists($view = $path . $_ci_file)) {
                    $_ci_path = $view;
                    break;
                }
                if (!$cascade) break;
            }
        } elseif (isset($_ci_path)) {
            $_ci_file = basename($_ci_path);
            if (!file_exists($_ci_path)) $_ci_path = '';
        }
        if (empty($_ci_path)) show_error('Unable to load the requested file: ' . $_ci_file);
        if (isset($_ci_vars)) $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, (array) $_ci_vars);
        extract($this->_ci_cached_vars);
        ob_start();
        if ((bool) @ini_get('short_open_tag') === FALSE && CI::$APP->config->item('rewrite_short_tags') == TRUE) {
            echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        } else {
            include($_ci_path);
        }
        log_message('debug', 'File loaded: ' . $_ci_path);
        if ($_ci_return == TRUE) return ob_get_clean();
        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        } else {
            CI::$APP->output->append_output(ob_get_clean());
        }
    }

    public function _autoloader($autoload)
    {
        $path = FALSE;
        if ($this->_module) {
            list($path, $file) = Modules::find('constants', $this->_module, 'config/');
            if ($path != FALSE) include_once $path . $file . '.php';
            list($path, $file) = Modules::find('autoload', $this->_module, 'config/');
            if ($path != FALSE) $autoload = array_merge(Modules::load_file($file, $path, 'autoload'), $autoload);
        }
        if (count($autoload) == 0) return;

        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) $this->add_package_path($package_path);
        }
        if (isset($autoload['config'])) {
            foreach ($autoload['config'] as $config) $this->config($config);
        }
        foreach (array('helper', 'plugin', 'language') as $type) {
            if (isset($autoload[$type])) {
                foreach ($autoload[$type] as $item) $this->$type($item);
            }
        }
        if (isset($autoload['libraries'])) {
            if (in_array('database', $autoload['libraries'])) {
                if (!$db = CI::$APP->config->item('database')) {
                    $this->database();
                    $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
                }
            }
            foreach ($autoload['libraries'] as $library => $alias) {
                (is_int($library)) ? $this->library($alias) : $this->library($library, NULL, $alias);
            }
        }
        if (isset($autoload['model'])) {
            foreach ($autoload['model'] as $model => $alias) {
                (is_int($model)) ? $this->model($alias) : $this->model($model, $alias);
            }
        }
        if (isset($autoload['modules'])) {
            foreach ($autoload['modules'] as $controller) {
                ($controller != $this->_module) && $this->module($controller);
            }
        }
    }
}

if (!class_exists('CI', FALSE)) {
    require dirname(__FILE__) . '/Ci.php';
}
