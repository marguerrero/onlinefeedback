<?php

/* FeedbackUserBundle:Default:index.html.twig */
class __TwigTemplate_e5b5f126837120ed4bf8b6dfba2c4a45c3ae2034589710b0d2e5ff761b4041d8 extends Twig_Template
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
            // asset "1ac31b4_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_1ac31b4_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/1ac31b4_bootstrap_1.css");
            // line 15
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
            // asset "1ac31b4_1"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_1ac31b4_1") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/1ac31b4_alertify.core_2.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
            // asset "1ac31b4_2"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_1ac31b4_2") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/1ac31b4_alertify.default_3.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
            // asset "1ac31b4_3"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_1ac31b4_3") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/1ac31b4_login_styles_4.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
        } else {
            // asset "1ac31b4"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_1ac31b4") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/1ac31b4.css");
            echo "      <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" />
    ";
        }
        unset($context["asset_url"]);
        // line 17
        echo "

    ";
        // line 19
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "63f7e1d_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d_jquery_1.js");
            // line 26
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
            // asset "63f7e1d_1"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d_1") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d_jquery.validate.min_2.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
            // asset "63f7e1d_2"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d_2") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d_additional-methods.min_3.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
            // asset "63f7e1d_3"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d_3") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d_alertify.min_4.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
            // asset "63f7e1d_4"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d_4") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d_bootstrap.min_5.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        } else {
            // asset "63f7e1d"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_63f7e1d") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/63f7e1d.js");
            echo "      <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        }
        unset($context["asset_url"]);
        // line 28
        echo "</head>
<body>
    <div class=\"container\">

<div class=\"row\" style=\"margin-top:20px\">
    <div class=\"col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3\">
        <form id=\"login\" role=\"form\">
            <fieldset>
                <h2>Please Sign In</h2>
                <hr class=\"colorgraph\">
                <div class=\"form-group\">
                    <input name=\"username\" id=\"email\" class=\"form-control input-lg\" placeholder=\"Username\">
                </div>
                <div class=\"form-group\">
                    <input type=\"password\" name=\"password\" id=\"password\" class=\"form-control input-lg\" placeholder=\"Password\">
                </div>
                <div class=\"row\">
                  <div class=\"col-lg-6\">
                    <div class=\"input-group\">
                      <span class=\"input-group-addon\">
                        <input type=\"checkbox\" name=\"anonymous\" id=\"anonymous\">
                      </span>
                      <input value=\"Login as anonymous\" disabled type=\"text\" class=\"form-control\">
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
                <hr class=\"colorgraph\">
                <div class=\"row\">
                    <div class=\"col-xs-6 col-sm-6 col-md-6\">
                        <input type=\"submit\" class=\"btn btn-lg btn-success btn-block\" id=\"btnLogin\" value=\"Login\">
                    </div>
                    <!-- <div class=\"col-xs-6 col-sm-6 col-md-6\">
                        <input type=\"submit\" class=\"btn btn-lg btn-primary btn-block\" value=\"Login as anonymous\">
                    </div> -->
                </div>
            </fieldset>
        </form>
    </div>
</div>

</div>  
<script type=\"text/javascript\">
    (function(){
        \$(document).ready(function () {
        
             \$(\"#anonymous\").click(function () {
                var cBox = \$(\"#anonymous\").is(':checked');
                \$(\"#email, #password\").prop('disabled', cBox);
             });
               
             \$(\"#login\").validate({
                submitHandler: function(form) {
                    \$.ajax({
                        url: 'login_check',
                        type: 'POST',
                        data: \$('#login').serialize(),
                        success: function(data)
                        {
                           if(alertify.alert(data.msg) === \"OK\");
                           {
                                window.location = \"http://cdo-apps2.concentrix.ph/online_feedback/web/app_dev.php/survey\";
                           }
                 
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                           alertify.alert(data.msg, wait);  
                        }   
                    });
                },
                rules: {
                    username: \"required\",
                    password: \"required\"
                },
                message: {
                    username: \"Username is required\",
                    password: \"Password is required\"
                }
             });
        });     
    })(); 
</script>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "FeedbackUserBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 28,  480 => 162,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 151,  444 => 149,  440 => 148,  437 => 147,  435 => 146,  430 => 144,  427 => 143,  423 => 142,  413 => 134,  409 => 132,  407 => 131,  402 => 130,  398 => 129,  393 => 126,  387 => 122,  384 => 121,  381 => 120,  379 => 119,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 105,  337 => 103,  322 => 101,  314 => 99,  312 => 98,  309 => 97,  305 => 95,  298 => 91,  294 => 90,  285 => 89,  283 => 88,  278 => 86,  268 => 85,  264 => 84,  258 => 81,  252 => 80,  247 => 78,  241 => 77,  229 => 73,  220 => 70,  214 => 69,  177 => 65,  169 => 60,  140 => 55,  132 => 51,  128 => 49,  107 => 36,  61 => 13,  273 => 96,  269 => 94,  254 => 92,  243 => 88,  240 => 86,  238 => 85,  235 => 74,  230 => 82,  227 => 81,  224 => 71,  221 => 77,  219 => 76,  217 => 75,  208 => 68,  204 => 72,  179 => 69,  159 => 61,  143 => 56,  135 => 53,  119 => 42,  102 => 32,  71 => 17,  67 => 15,  63 => 15,  59 => 14,  87 => 25,  201 => 92,  196 => 90,  183 => 82,  171 => 61,  166 => 71,  163 => 62,  158 => 67,  156 => 66,  151 => 63,  142 => 59,  138 => 54,  136 => 56,  121 => 46,  117 => 44,  105 => 40,  91 => 27,  62 => 23,  49 => 19,  38 => 6,  28 => 8,  21 => 2,  25 => 4,  94 => 28,  89 => 20,  85 => 25,  75 => 17,  68 => 19,  56 => 9,  24 => 3,  31 => 5,  26 => 6,  19 => 1,  93 => 28,  88 => 6,  78 => 21,  46 => 7,  44 => 12,  27 => 4,  79 => 18,  72 => 26,  69 => 25,  47 => 9,  40 => 7,  37 => 10,  22 => 2,  246 => 90,  157 => 56,  145 => 46,  139 => 45,  131 => 52,  123 => 47,  120 => 40,  115 => 43,  111 => 37,  108 => 36,  101 => 32,  98 => 31,  96 => 31,  83 => 25,  74 => 14,  66 => 15,  55 => 15,  52 => 21,  50 => 10,  43 => 8,  41 => 9,  35 => 5,  32 => 15,  29 => 3,  209 => 82,  203 => 78,  199 => 67,  193 => 73,  189 => 71,  187 => 84,  182 => 66,  176 => 64,  173 => 65,  168 => 72,  164 => 59,  162 => 57,  154 => 58,  149 => 51,  147 => 58,  144 => 49,  141 => 48,  133 => 55,  130 => 41,  125 => 44,  122 => 43,  116 => 41,  112 => 42,  109 => 34,  106 => 36,  103 => 32,  99 => 31,  95 => 28,  92 => 21,  86 => 28,  82 => 22,  80 => 19,  73 => 19,  64 => 17,  60 => 6,  57 => 11,  54 => 10,  51 => 14,  48 => 8,  45 => 17,  42 => 7,  39 => 9,  36 => 5,  33 => 4,  30 => 7,);
    }
}
