<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/css/1ac31b4')) {
            // _assetic_1ac31b4
            if ($pathinfo === '/css/1ac31b4.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '1ac31b4',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_1ac31b4',);
            }

            if (0 === strpos($pathinfo, '/css/1ac31b4_')) {
                // _assetic_1ac31b4_0
                if ($pathinfo === '/css/1ac31b4_bootstrap_1.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '1ac31b4',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_1ac31b4_0',);
                }

                if (0 === strpos($pathinfo, '/css/1ac31b4_alertify.')) {
                    // _assetic_1ac31b4_1
                    if ($pathinfo === '/css/1ac31b4_alertify.core_2.css') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '1ac31b4',  'pos' => 1,  '_format' => 'css',  '_route' => '_assetic_1ac31b4_1',);
                    }

                    // _assetic_1ac31b4_2
                    if ($pathinfo === '/css/1ac31b4_alertify.default_3.css') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '1ac31b4',  'pos' => 2,  '_format' => 'css',  '_route' => '_assetic_1ac31b4_2',);
                    }

                }

                // _assetic_1ac31b4_3
                if ($pathinfo === '/css/1ac31b4_login_styles_4.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '1ac31b4',  'pos' => 3,  '_format' => 'css',  '_route' => '_assetic_1ac31b4_3',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/js/63f7e1d')) {
            // _assetic_63f7e1d
            if ($pathinfo === '/js/63f7e1d.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_63f7e1d',);
            }

            if (0 === strpos($pathinfo, '/js/63f7e1d_')) {
                if (0 === strpos($pathinfo, '/js/63f7e1d_jquery')) {
                    // _assetic_63f7e1d_0
                    if ($pathinfo === '/js/63f7e1d_jquery_1.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_63f7e1d_0',);
                    }

                    // _assetic_63f7e1d_1
                    if ($pathinfo === '/js/63f7e1d_jquery.validate.min_2.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_63f7e1d_1',);
                    }

                }

                if (0 === strpos($pathinfo, '/js/63f7e1d_a')) {
                    // _assetic_63f7e1d_2
                    if ($pathinfo === '/js/63f7e1d_additional-methods.min_3.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => 2,  '_format' => 'js',  '_route' => '_assetic_63f7e1d_2',);
                    }

                    // _assetic_63f7e1d_3
                    if ($pathinfo === '/js/63f7e1d_alertify.min_4.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => 3,  '_format' => 'js',  '_route' => '_assetic_63f7e1d_3',);
                    }

                }

                // _assetic_63f7e1d_4
                if ($pathinfo === '/js/63f7e1d_bootstrap.min_5.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '63f7e1d',  'pos' => 4,  '_format' => 'js',  '_route' => '_assetic_63f7e1d_4',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/css/dc4b8f7')) {
            // _assetic_dc4b8f7
            if ($pathinfo === '/css/dc4b8f7.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'dc4b8f7',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_dc4b8f7',);
            }

            if (0 === strpos($pathinfo, '/css/dc4b8f7_')) {
                // _assetic_dc4b8f7_0
                if ($pathinfo === '/css/dc4b8f7_bootstrap_1.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'dc4b8f7',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_dc4b8f7_0',);
                }

                // _assetic_dc4b8f7_1
                if ($pathinfo === '/css/dc4b8f7_thank_you_2.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'dc4b8f7',  'pos' => 1,  '_format' => 'css',  '_route' => '_assetic_dc4b8f7_1',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/js/a8ff99a')) {
            // _assetic_a8ff99a
            if ($pathinfo === '/js/a8ff99a.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'a8ff99a',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_a8ff99a',);
            }

            if (0 === strpos($pathinfo, '/js/a8ff99a_')) {
                // _assetic_a8ff99a_0
                if ($pathinfo === '/js/a8ff99a_jquery_1.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'a8ff99a',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_a8ff99a_0',);
                }

                // _assetic_a8ff99a_1
                if ($pathinfo === '/js/a8ff99a_bootstrap.min_2.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'a8ff99a',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_a8ff99a_1',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/css')) {
            if (0 === strpos($pathinfo, '/css/732e0d5')) {
                // _assetic_732e0d5
                if ($pathinfo === '/css/732e0d5.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '732e0d5',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_732e0d5',);
                }

                if (0 === strpos($pathinfo, '/css/732e0d5_')) {
                    // _assetic_732e0d5_0
                    if ($pathinfo === '/css/732e0d5_bootstrap_1.css') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '732e0d5',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_732e0d5_0',);
                    }

                    // _assetic_732e0d5_1
                    if ($pathinfo === '/css/732e0d5_login_styles_2.css') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '732e0d5',  'pos' => 1,  '_format' => 'css',  '_route' => '_assetic_732e0d5_1',);
                    }

                }

            }

            if (0 === strpos($pathinfo, '/css/4e45f16')) {
                // _assetic_4e45f16
                if ($pathinfo === '/css/4e45f16.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '4e45f16',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_4e45f16',);
                }

                // _assetic_4e45f16_0
                if ($pathinfo === '/css/4e45f16_ext-theme-neptune-all_1.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '4e45f16',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_4e45f16_0',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/js')) {
            if (0 === strpos($pathinfo, '/js/7a16d64')) {
                // _assetic_7a16d64
                if ($pathinfo === '/js/7a16d64.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '7a16d64',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_7a16d64',);
                }

                if (0 === strpos($pathinfo, '/js/7a16d64_')) {
                    if (0 === strpos($pathinfo, '/js/7a16d64_ext-')) {
                        // _assetic_7a16d64_0
                        if ($pathinfo === '/js/7a16d64_ext-all-debug_1.js') {
                            return array (  '_controller' => 'assetic.controller:render',  'name' => '7a16d64',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_7a16d64_0',);
                        }

                        // _assetic_7a16d64_1
                        if ($pathinfo === '/js/7a16d64_ext-theme-neptune_2.js') {
                            return array (  '_controller' => 'assetic.controller:render',  'name' => '7a16d64',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_7a16d64_1',);
                        }

                    }

                    // _assetic_7a16d64_2
                    if ($pathinfo === '/js/7a16d64_pantry_feedback_report_3.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '7a16d64',  'pos' => 2,  '_format' => 'js',  '_route' => '_assetic_7a16d64_2',);
                    }

                }

            }

            if (0 === strpos($pathinfo, '/js/a258bbd')) {
                // _assetic_a258bbd
                if ($pathinfo === '/js/a258bbd.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'a258bbd',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_a258bbd',);
                }

                if (0 === strpos($pathinfo, '/js/a258bbd_')) {
                    if (0 === strpos($pathinfo, '/js/a258bbd_ext-')) {
                        // _assetic_a258bbd_0
                        if ($pathinfo === '/js/a258bbd_ext-all-debug_1.js') {
                            return array (  '_controller' => 'assetic.controller:render',  'name' => 'a258bbd',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_a258bbd_0',);
                        }

                        // _assetic_a258bbd_1
                        if ($pathinfo === '/js/a258bbd_ext-theme-neptune_2.js') {
                            return array (  '_controller' => 'assetic.controller:render',  'name' => 'a258bbd',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_a258bbd_1',);
                        }

                    }

                    // _assetic_a258bbd_2
                    if ($pathinfo === '/js/a258bbd_pantry_maintenance_3.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => 'a258bbd',  'pos' => 2,  '_format' => 'js',  '_route' => '_assetic_a258bbd_2',);
                    }

                }

            }

        }

        if (0 === strpos($pathinfo, '/css/3694d9c')) {
            // _assetic_3694d9c
            if ($pathinfo === '/css/3694d9c.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '3694d9c',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_3694d9c',);
            }

            if (0 === strpos($pathinfo, '/css/3694d9c_')) {
                // _assetic_3694d9c_0
                if ($pathinfo === '/css/3694d9c_bootstrap_1.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '3694d9c',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_3694d9c_0',);
                }

                // _assetic_3694d9c_1
                if ($pathinfo === '/css/3694d9c_dashboard_2.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '3694d9c',  'pos' => 1,  '_format' => 'css',  '_route' => '_assetic_3694d9c_1',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/js/534d7b2')) {
            // _assetic_534d7b2
            if ($pathinfo === '/js/534d7b2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '534d7b2',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_534d7b2',);
            }

            if (0 === strpos($pathinfo, '/js/534d7b2_')) {
                if (0 === strpos($pathinfo, '/js/534d7b2_jquery')) {
                    // _assetic_534d7b2_0
                    if ($pathinfo === '/js/534d7b2_jquery_1.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '534d7b2',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_534d7b2_0',);
                    }

                    // _assetic_534d7b2_1
                    if ($pathinfo === '/js/534d7b2_jquery.validate.min_2.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => '534d7b2',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_534d7b2_1',);
                    }

                }

                // _assetic_534d7b2_2
                if ($pathinfo === '/js/534d7b2_additional-methods.min_3.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '534d7b2',  'pos' => 2,  '_format' => 'js',  '_route' => '_assetic_534d7b2_2',);
                }

                // _assetic_534d7b2_3
                if ($pathinfo === '/js/534d7b2_bootstrap.min_4.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '534d7b2',  'pos' => 3,  '_format' => 'js',  '_route' => '_assetic_534d7b2_3',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_purge
                if ($pathinfo === '/_profiler/purge') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:purgeAction',  '_route' => '_profiler_purge',);
                }

                if (0 === strpos($pathinfo, '/_profiler/i')) {
                    // _profiler_info
                    if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                    }

                    // _profiler_import
                    if ($pathinfo === '/_profiler/import') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:importAction',  '_route' => '_profiler_import',);
                    }

                }

                // _profiler_export
                if (0 === strpos($pathinfo, '/_profiler/export') && preg_match('#^/_profiler/export/(?P<token>[^/\\.]++)\\.txt$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_export')), array (  '_controller' => 'web_profiler.controller.profiler:exportAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            if (0 === strpos($pathinfo, '/_configurator')) {
                // _configurator_home
                if (rtrim($pathinfo, '/') === '/_configurator') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_configurator_home');
                    }

                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
                }

                // _configurator_step
                if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_configurator_step')), array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',));
                }

                // _configurator_final
                if ($pathinfo === '/_configurator/final') {
                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/cx-admin')) {
            // _cx_admin
            if (rtrim($pathinfo, '/') === '/cx-admin') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_cx_admin');
                }

                return array (  '_controller' => 'Admin\\LoginBundle\\Controller\\DefaultController::indexAction',  '_route' => '_cx_admin',);
            }

            // _cx_admin_check
            if ($pathinfo === '/cx-admin/cx-admin-check') {
                return array (  '_controller' => 'Admin\\LoginBundle\\Controller\\DefaultController::cxAdminCheck',  '_route' => '_cx_admin_check',);
            }

        }

        if (0 === strpos($pathinfo, '/maintenance')) {
            // _maintenance
            if (rtrim($pathinfo, '/') === '/maintenance') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_maintenance');
                }

                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::indexAction',  '_route' => '_maintenance',);
            }

            // _category_list
            if ($pathinfo === '/maintenance/category-list') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::categoryList',  '_route' => '_category_list',);
            }

            // _save_category
            if ($pathinfo === '/maintenance/save-category') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::saveCategory',  '_route' => '_save_category',);
            }

            // _load_category
            if ($pathinfo === '/maintenance/load-category') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::loadCategory',  '_route' => '_load_category',);
            }

            // _delete_category
            if ($pathinfo === '/maintenance/delete-category') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::deleteCategory',  '_route' => '_delete_category',);
            }

            // _save_question
            if ($pathinfo === '/maintenance/save-question') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::saveQuestion',  '_route' => '_save_question',);
            }

            // _load_question
            if ($pathinfo === '/maintenance/load-question') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::loadQuestion',  '_route' => '_load_question',);
            }

            // _delete_question
            if ($pathinfo === '/maintenance/delete-question') {
                return array (  '_controller' => 'Admin\\MaintenanceBundle\\Controller\\DefaultController::deleteQuestion',  '_route' => '_delete_question',);
            }

        }

        // _report
        if (rtrim($pathinfo, '/') === '/report') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_report');
            }

            return array (  '_controller' => 'Admin\\ReportBundle\\Controller\\DefaultController::indexAction',  '_route' => '_report',);
        }

        // _survey
        if (rtrim($pathinfo, '/') === '/survey') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_survey');
            }

            return array (  '_controller' => 'Feedback\\SurveyFormBundle\\Controller\\DefaultController::indexAction',  '_route' => '_survey',);
        }

        if (0 === strpos($pathinfo, '/login')) {
            // _login
            if (rtrim($pathinfo, '/') === '/login') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_login');
                }

                return array (  '_controller' => 'Feedback\\UserBundle\\Controller\\DefaultController::indexAction',  '_route' => '_login',);
            }

            // _login_check
            if ($pathinfo === '/login/login_check') {
                return array (  '_controller' => 'Feedback\\UserBundle\\Controller\\DefaultController::loginCheck',  '_route' => '_login_check',);
            }

            // _thank_you
            if ($pathinfo === '/login/thank_you') {
                return array (  '_controller' => 'Feedback\\UserBundle\\Controller\\ThankYouController::indexAction',  '_route' => '_thank_you',);
            }

        }

        // _welcome
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_welcome');
            }

            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\WelcomeController::indexAction',  '_route' => '_welcome',);
        }

        if (0 === strpos($pathinfo, '/demo')) {
            if (0 === strpos($pathinfo, '/demo/secured')) {
                if (0 === strpos($pathinfo, '/demo/secured/log')) {
                    if (0 === strpos($pathinfo, '/demo/secured/login')) {
                        // _demo_login
                        if ($pathinfo === '/demo/secured/login') {
                            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::loginAction',  '_route' => '_demo_login',);
                        }

                        // _demo_security_check
                        if ($pathinfo === '/demo/secured/login_check') {
                            return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::securityCheckAction',  '_route' => '_demo_security_check',);
                        }

                    }

                    // _demo_logout
                    if ($pathinfo === '/demo/secured/logout') {
                        return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::logoutAction',  '_route' => '_demo_logout',);
                    }

                }

                if (0 === strpos($pathinfo, '/demo/secured/hello')) {
                    // acme_demo_secured_hello
                    if ($pathinfo === '/demo/secured/hello') {
                        return array (  'name' => 'World',  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloAction',  '_route' => 'acme_demo_secured_hello',);
                    }

                    // _demo_secured_hello
                    if (preg_match('#^/demo/secured/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => '_demo_secured_hello')), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloAction',));
                    }

                    // _demo_secured_hello_admin
                    if (0 === strpos($pathinfo, '/demo/secured/hello/admin') && preg_match('#^/demo/secured/hello/admin/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => '_demo_secured_hello_admin')), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\SecuredController::helloadminAction',));
                    }

                }

            }

            // _demo
            if (rtrim($pathinfo, '/') === '/demo') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_demo');
                }

                return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::indexAction',  '_route' => '_demo',);
            }

            // _demo_hello
            if (0 === strpos($pathinfo, '/demo/hello') && preg_match('#^/demo/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_demo_hello')), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::helloAction',));
            }

            // _demo_contact
            if ($pathinfo === '/demo/contact') {
                return array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::contactAction',  '_route' => '_demo_contact',);
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
