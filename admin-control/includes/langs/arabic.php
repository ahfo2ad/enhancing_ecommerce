<?php

    function language($phrase) {

        static $lang = array(

            "main-page"  => "الرئيسية",
            "sections"   => "الاقسام",
            "to-store"   => "المتجر",
            "edit-data"  => "الملف الشخصى",
            "changes"    => "الاعدادات",
            "exit"       => "تسجيل خروج"
        );

        return $lang[$phrase];
    }