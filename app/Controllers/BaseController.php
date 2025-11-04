<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;
use App\Models\FontModel;
use CodeIgniter\View\View;

helper('cookie');
/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $session;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    protected $defaultFont;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        helper('cookie');
        $this->session = session();

        if (!$this->session->get('logged_in')) {

            $rememberToken = get_cookie('remember_token');

            if ($rememberToken) {
                $userModel = new UserModel();
                $user = $userModel->where('remember_token', $rememberToken)->first();

                if ($user) {
                    // Auto-login user
                    $this->session->set([
                        'user_id'   => $user['id'],
                        'full_name' => $user['firstname'] . ' ' . $user['lastname'],
                        'username'  => $user['username'],
                        'email'     => $user['email'],
                        'role'      => $user['role'],
                        'logged_in' => true,
                    ]);
                } else {
                    // Invalid token: delete cookie to clean up
                    delete_cookie('remember_token', '/');
                }
            }
        }

        // load default font
        $fontModel = new FontModel();
        $fontRow = $fontModel->where('is_default', 1)->first();
        $this->defaultFont = $fontRow['font_name'] ?? 'Roboto';
        log_message('debug', 'Default font selected: ' . $this->defaultFont);

        // ✅ Set global view variable
        service('renderer')->setVar('defaultFont', $this->defaultFont);
    }
}
