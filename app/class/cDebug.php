<?php

//-----------------------------------------------------------------------------
class debug {

    // Activation ou désactivation du mode Debug
    // Activez le mode Debug en Développement et désactivez le mode en Production

    private $debugMode = K_DEBUG;

    public function AKDebug($variable, $nomVariable = null)
    {

        if ($this->debugMode) {

            $debug = debug_backtrace();

            echo '<div id="ktdebug">';

            echo '<hr>';
            echo '<h3> Debug informations</h3>';

            echo '<p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;" ><strong> ' . $debug[0]['file'] . ' </strong>: Ligne: ' . $debug[0]['line'] . '</a></p>';
            echo '<ol style="display: none;">';

            foreach ($debug as $key => $value) {
                if ($key > 0) {
                    echo '<li><strong> ' . $value['file'] . ' </strong>: Ligne: ' . $value['line'] . '</li>';
                }
            }

            echo '</ol>';

            echo '<p>Valeur de: ' . $nomVariable . '</p>';
            echo '<pre>';
            print_r($variable);
            echo '</pre>';
            echo '<hr>';

            echo '</div>';
        }
        die();
    }

    public function AKvardump($var)
    {
        $string = '<pre>';
        ob_start();
        var_dump($var);
        $string .= ob_get_clean();
        $string .= '</pre>';
        return $string;
    }
}

// End of class

