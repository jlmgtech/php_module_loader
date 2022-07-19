<?php

class ExampleCore {

    public static function onload() { }

    public function render($loader) {
        $modconf = $loader->modconf;
        $output = "";

        printf("AVAILABLE MODULES:\n");
        foreach ($loader->get_all_modules() as $module) {
            printf("\t%s\n", $module);
            printf("\t\tAVAILABLE DRIVERS:\n");
            foreach ($loader->get_all_drivers_for_module($module) as $driver) {
                printf("\t\t\t%s\n", $driver);
            }
        }
        printf("\n");

        printf("ENABLED MODULES:\n");
        foreach ($loader->get_enabled_modules() as $module) {
            printf("\t%s\n", $module);
            printf("\t\tSELECTED DRIVERS:\n");
            foreach ($loader->modconf[$module] as $driver) {
                printf("\t\t\t%s\n", $driver);
            }
            printf("\n");

            printf("\t\tAVAILABLE DRIVERS:\n");
            foreach ($loader->get_all_drivers_for_module($module) as $driver) {
                printf("\t\t\t%s\n", $driver);
            }
            printf("\n");
        }
        printf("\n");

        printf("DISABLED MODULES:\n");
        foreach($loader->get_disabled_modules() as $module) {
            printf("\t%s\n", $module);
            printf("\t\tAVAILABLE DRIVERS:\n");
            foreach ($loader->get_all_drivers_for_module($module) as $driver) {
                printf("\t\t\t%s\n", $driver);
            }
            printf("\n");
        }
        printf("\n");
    }

};

