<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class MX_Router extends CI_Router
{
    protected function _set_default_controller()
    {
        if (empty($this->default_controller)) {
            show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
        }
        $segments = explode('/', $this->default_controller);
        if (count($segments) == 2) {
            $this->set_class($segments[0]);
            $this->set_method($segments[1]);
        } else {
            $this->set_class($this->default_controller);
            $this->set_method('index');
        }
        $suffix = (string) $this->config->item('controller_suffix');
        if ($suffix !== '' && strpos($this->class, $suffix) === FALSE) {
            $this->class .= $suffix;
        }
        $this->_set_request(array($this->class, $this->method));
        $this->_set_overrides($this->routes);
    }
}
