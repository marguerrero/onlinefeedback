<?php

/* FeedbackSurveyFormBundle:Default:index.html.twig */
class __TwigTemplate_32673e0864130a3281ad89cb40c1c9efeaade0de5824d5a304a64113e3662ff1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <title>Concentrix Feedback Form</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
   ";
        // line 8
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "732e0d5_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_732e0d5_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/732e0d5_bootstrap_1.css");
            // line 13
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
            // asset "732e0d5_1"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_732e0d5_1") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/732e0d5_login_styles_2.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
        } else {
            // asset "732e0d5"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_732e0d5") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/732e0d5.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
        }
        unset($context["asset_url"]);
        // line 15
        echo "

    ";
        // line 17
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "a8ff99a_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_a8ff99a_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/a8ff99a_jquery_1.js");
            // line 21
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
            // asset "a8ff99a_1"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_a8ff99a_1") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/a8ff99a_bootstrap.min_2.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        } else {
            // asset "a8ff99a"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_a8ff99a") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/a8ff99a.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        }
        unset($context["asset_url"]);
        // line 23
        echo "    
    <script type=\"text/javascript\">
        window.alert = function(){};
        var defaultCSS = document.getElementById('bootstrap-css');
        function changeCSS(css){
            if(css) \$('head > link').filter(':first').replaceWith('<link rel=\"stylesheet\" href=\"'+ css +'\" type=\"text/css\" />'); 
            else \$('head > link').filter(':first').replaceWith(defaultCSS); 
        }
        \$( document ).ready(function() {
          var iframe_height = parseInt(\$('html').height()); 
          window.parent.postMessage( iframe_height, 'http://bootsnipp.com');
        });
    </script>
</head>
<body>
    <div class=\"container\">

<div class=\"row\" style=\"margin-top:20px\">
    <div class=\"col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3\">
        <div class=\"table-responsive\">
            <table class=\"table table-bordered text-center\">
                <thead>
                    <tr>Assessment Guide</tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-center\" width=100>1</td>
                        <td class=\"text-center\" width=100>2</td>
                        <td class=\"text-center\" width=100>3</td>
                        <td class=\"text-center\" width=100>4</td>
                        <td class=\"text-center\" width=100>5</td>
                    </tr>
                    <tr>
                        <td class=\"text-center\" width=100>Unacceptable</td>
                        <td class=\"text-center\" width=100>Poor</td>
                        <td class=\"text-center\" width=100>Satisfactory</td>
                        <td class=\"text-center\" width=100>Good</td>
                        <td class=\"text-center\" width=100>Excellent</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <form role=\"form\" method=\"POST\" action=\"";
        // line 65
        echo $this->env->getExtension('routing')->getPath("_login_check");
        echo "\">
            <table class=\"table text-center\">
                <thead>
                    <tr>Cleanliness</tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pantry is clean</td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">1
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">2
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">3
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">4
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">5
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Pantry is clean</td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">1
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">2
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">3
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">4
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">5
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Staff proper hygiene (hair net, gloves, etc</td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">1
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">2
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">3
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">4
                            </span>
                        </td>
                        <td class=\"text-center\" >
                            <span class=\"input-group-addon\">
                                <input value='1' id='question_1' name='question_1' type=\"radio\">5
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <div class=\"row\">
            <h4>Cleanliness</h4>
            <div class=\"row\">
             <div class=\"col-lg-12\">
                <div class=\"input-group\">
                  
              <div class=\"col-lg-12\">
                <div class=\"input-group\">
                  <span class=\"input-group-addon question\">
                        Pantry is Clean
                  </span>
                  <span class=\"input-group-addon\">
                    <input value='1' id='question_1' name='question_1' type=\"radio\">1
                  </span>
                  <span class=\"input-group-addon\">
                    <input value='2' id='question_1' name='question_1' type=\"radio\">2
                  </span>
                  <span class=\"input-group-addon\">
                    <input value='3' id='question_1' name='question_1' type=\"radio\">3
                  </span>
                  <span class=\"input-group-addon\">
                    <input value='4' id='question_1' name='question_1' type=\"radio\">4
                  </span>
                  <span class=\"input-group-addon\">
                    <input value='5' id='question_1' name='question_1' type=\"radio\">5
                  </span>
                </div><!-- /input-group -->
                <div class=\"col-lg-12\">
                    <div class=\"input-group center-buttons\">
                        <div class=\"row container-fluid  form_navigator center-buttons\">
                            <div class=\"input-group\">
                                <span class=\"input-group-addon col-lg-12\">
                                    <button type=\"button\" class=\"btn btn-default\">Previous</button>
                                </span>
                                <span class=\"input-group-addon col-lg-12\">
                                    <button type=\"button\" class=\"btn btn-success\">Submit</button>
                                </span>
                                <span class=\"input-group-addon col-lg-12\">
                                    <button type=\"button\" class=\"btn btn-default\">Next</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>
        </form>
            
    </div>
</div>

</div>  <script type=\"text/javascript\">
    \$(function(){
    \$('.button-checkbox').each(function(){
        var \$widget = \$(this),
            \$button = \$widget.find('button'),
            \$checkbox = \$widget.find('input:checkbox'),
            color = \$button.data('color'),
            settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
            };

        \$button.on('click', function () {
            \$checkbox.prop('checked', !\$checkbox.is(':checked'));
            \$checkbox.triggerHandler('change');
            updateDisplay();
        });

        \$checkbox.on('change', function () {
            updateDisplay();
        });

        function updateDisplay() {
            var isChecked = \$checkbox.is(':checked');
            // Set the button's state
            \$button.data('state', (isChecked) ? \"on\" : \"off\");

            // Set the button's icon
            \$button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[\$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                \$button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else
            {
                \$button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }
        function init() {
            updateDisplay();
            // Inject the icon if applicable
            if (\$button.find('.state-icon').length == 0) {
                \$button.prepend('<i class=\"state-icon ' + settings[\$button.data('state')].icon + '\"></i> ');
            }
        }
        init();
    });
}); </script>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "FeedbackSurveyFormBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 65,  84 => 27,  110 => 28,  480 => 162,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 151,  444 => 149,  440 => 148,  437 => 147,  435 => 146,  430 => 144,  427 => 143,  423 => 142,  413 => 134,  409 => 132,  407 => 131,  402 => 130,  398 => 129,  393 => 126,  387 => 122,  384 => 121,  381 => 120,  379 => 119,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 105,  337 => 103,  322 => 101,  314 => 99,  312 => 98,  309 => 97,  305 => 95,  298 => 91,  294 => 90,  285 => 89,  283 => 88,  278 => 86,  268 => 85,  264 => 84,  258 => 81,  252 => 80,  247 => 78,  241 => 77,  229 => 73,  220 => 70,  214 => 69,  177 => 65,  169 => 60,  140 => 55,  132 => 51,  128 => 49,  107 => 36,  61 => 13,  273 => 96,  269 => 94,  254 => 92,  243 => 88,  240 => 86,  238 => 85,  235 => 74,  230 => 82,  227 => 81,  224 => 71,  221 => 77,  219 => 76,  217 => 75,  208 => 68,  204 => 72,  179 => 69,  159 => 61,  143 => 56,  135 => 53,  119 => 42,  102 => 32,  71 => 17,  67 => 15,  63 => 15,  59 => 14,  87 => 25,  201 => 92,  196 => 90,  183 => 82,  171 => 61,  166 => 71,  163 => 62,  158 => 67,  156 => 66,  151 => 63,  142 => 59,  138 => 54,  136 => 56,  121 => 46,  117 => 44,  105 => 40,  91 => 27,  62 => 23,  49 => 19,  38 => 6,  28 => 8,  21 => 2,  25 => 4,  94 => 28,  89 => 20,  85 => 25,  75 => 17,  68 => 19,  56 => 17,  24 => 3,  31 => 5,  26 => 6,  19 => 1,  93 => 28,  88 => 6,  78 => 21,  46 => 7,  44 => 12,  27 => 4,  79 => 18,  72 => 26,  69 => 25,  47 => 9,  40 => 7,  37 => 10,  22 => 2,  246 => 90,  157 => 56,  145 => 46,  139 => 45,  131 => 52,  123 => 47,  120 => 40,  115 => 43,  111 => 37,  108 => 36,  101 => 32,  98 => 31,  96 => 31,  83 => 25,  74 => 14,  66 => 15,  55 => 15,  52 => 15,  50 => 10,  43 => 8,  41 => 9,  35 => 5,  32 => 13,  29 => 3,  209 => 82,  203 => 78,  199 => 67,  193 => 73,  189 => 71,  187 => 84,  182 => 66,  176 => 64,  173 => 65,  168 => 72,  164 => 59,  162 => 57,  154 => 58,  149 => 51,  147 => 58,  144 => 49,  141 => 48,  133 => 55,  130 => 41,  125 => 44,  122 => 43,  116 => 41,  112 => 42,  109 => 34,  106 => 36,  103 => 32,  99 => 31,  95 => 28,  92 => 21,  86 => 28,  82 => 22,  80 => 23,  73 => 19,  64 => 25,  60 => 21,  57 => 11,  54 => 10,  51 => 14,  48 => 8,  45 => 17,  42 => 7,  39 => 9,  36 => 17,  33 => 4,  30 => 7,);
    }
}
