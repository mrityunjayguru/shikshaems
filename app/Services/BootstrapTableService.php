<?php

namespace App\Services;

class BootstrapTableService
{
    private static string $defaultClasses = "btn btn-xs btn-rounded btn-icon";

    /**
     * @param string $iconClass - Can be a CSS class (e.g., "fa fa-edit") or SVG markup (e.g., "<svg>...</svg>")
     * @param string $url
     * @param array $customClass
     * @param array $customAttributes
     * @return string
     */
    public static function button(string $iconClass, string $url, array $customClass = [], array $customAttributes = [])
    {
        $customClassStr = implode(" ", $customClass);
        $class = self::$defaultClasses . ' ' . $customClassStr;
        $attributes = '';
        if (count($customAttributes) > 0) {
            foreach ($customAttributes as $key => $value) {
                $attributes .= $key . '="' . $value . '" ';
            }
        }

        // Check if iconClass is SVG markup
        $iconHtml = '';
        if (stripos(trim($iconClass), '<svg') === 0) {
            // It's SVG markup, render directly
            $iconHtml = $iconClass;
        } else {
            // It's a CSS class, wrap in <i> tag
            $iconHtml = '<i class="' . $iconClass . '"></i>';
        }

        return '<a href="' . $url . '" class="' . $class . '" ' . $attributes . '>' . $iconHtml . '</a>&nbsp;&nbsp;';
    }

    /**
     * @param $url
     * @param bool $modal
     * @return string
     */
    public static function editButton($url, bool $modal = true)
    {
        $customClass = ["edit-data", "btn-gradient-primary"];
        $customAttributes = [
            "title" => trans("Edit")
        ];
        if ($modal) {
            $customAttributes = [
                "title" => "Edit",
                "data-toggle" => "modal",
                "data-target" => "#editModal"
            ];

            $customClass[] = "set-form-url";
        }

        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
        return self::button($iconClass, $url, $customClass, $customAttributes);
    }

    /**
     * @param $url
     * @return string
     */
    public static function deleteButton($url)
    {
        $customClass = ["delete-form", "btn-gradient-dark"];
        $customAttributes = [
            "title" => trans("Delete"),
        ];
        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.9 9.04997C15.72 8.82997 13.52 8.71997 11.33 8.71997C10.03 8.71997 8.72997 8.78997 7.43997 8.91997L6.09998 9.04997" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9.70996 8.38994L9.84996 7.52994C9.94996 6.90994 10.03 6.43994 11.14 6.43994H12.86C13.97 6.43994 14.0499 6.92994 14.1499 7.52994L14.2899 8.37994" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.49 9.12988L16.06 15.7299C15.99 16.7599 15.93 17.5599 14.1 17.5599H9.89C8.06 17.5599 7.99999 16.7599 7.92999 15.7299L7.5 9.12988" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
        return self::button($iconClass, $url, $customClass, $customAttributes);
    }

    /**
     * @param $url
     * @param string $title
     * @return string
     */
    public static function restoreButton($url, string $title = "Restore")
    {
        $customClass = ["btn-gradient-success", "restore-data"];
        $customAttributes = [
            "title" => trans($title),
        ];
        $iconClass = "fa fa-refresh action-icon";
        return self::button($iconClass, $url, $customClass, $customAttributes);
    }

    /**
     * @param $url
     * @return string
     */
    public static function trashButton($url)
    {
        $customClass = ["btn-gradient-dark", "trash-data"];
        $customAttributes = [
            "title" => trans("Delete Permanent"),
        ];
        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.16992 14.8299L14.8299 9.16992" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.8299 14.8299L9.16992 9.16992" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
        return self::button($iconClass, $url, $customClass, $customAttributes);
    }

    // public static function view($url)
    // {
    //     $customClass = ["btn-gradient-danger", "trash-data"];
    //     $customAttributes = [
    //         "title" => trans("Delete Permanent"),
    //     ];
    //     $iconClass = "fa fa-times";
    //     return self::button($iconClass, $url, $customClass, $customAttributes);
    // }


    /**
     * @param $url
     * @return string
     */
    public static function viewRelatedDataButton($url,  bool $modal = true)
    {
        $customClass = ["edit-data", "btn-eye"];
        $customAttributes = [
            "title" => trans("View Related Data")
        ];
        if ($modal) {
            $customAttributes = [
                "title" => "Edit",
                "data-toggle" => "modal",
                "data-target" => "#editModal"
            ];

            $customClass[] = "set-form-url";
        }

        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.5799 11.9999C15.5799 13.9799 13.9799 15.5799 11.9999 15.5799C10.0199 15.5799 8.41992 13.9799 8.41992 11.9999C8.41992 10.0199 10.0199 8.41992 11.9999 8.41992C13.9799 8.41992 15.5799 10.0199 15.5799 11.9999Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        return self::button($iconClass, $url, $customClass, $customAttributes);
    }

    // Menu list

    public static function menuButton($title, $url, $customClass = [], $customAttributes = [])
    {
        $attributes = '';
        $customClassStr = implode(" ", $customClass);
        if (count($customAttributes) > 0) {
            foreach ($customAttributes as $key => $value) {
                $attributes .= $key . '="' . $value . '" ';
            }
        }
        return '<a href="' . $url . '" class="dropdown-item p-2 ' . $customClassStr . '" ' . $attributes . '>' . trans($title) . '</a>';
    }

    public static function menuEditButton($title, $url, bool $modal = true)
    {
        $customClass = ["edit-data"];
        $customAttributes = [];
        if ($modal) {
            $customAttributes = [
                "data-toggle" => "modal",
                "data-target" => "#editModal"
            ];

            $customClass[] = " set-form-url";
        }

        return self::menuButton($title, $url, $customClass, $customAttributes);
    }

    public static function menuDeleteButton($title, $url)
    {
        $customClass = ["delete-form"];
        $customAttributes = [
            "title" => trans("Delete"),
        ];
        return self::menuButton($title, $url, $customClass, $customAttributes);
    }


    public static function menuRestoreButton($title, $url)
    {
        $customClass = ["restore-data"];
        $customAttributes = [];
        return self::menuButton($title, $url, $customClass, $customAttributes);
    }

    public static function menuTrashButton($title, $url)
    {
        $customClass = ["trash-data"];
        $customAttributes = [];
        return self::menuButton($title, $url, $customClass, $customAttributes);
    }

    public static function menuItem($operate)
    {

        // return '<div class="dropdown"> <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Dropdown </button> <div class="dropdown-menu" aria-labelledby="dropdownMenu2"> '. $operate .' </div> </div>';

        return '<div class="dropdown table-action-column d-flex align-items-center"> <button class="btn btn-sm btn-inverse-dark d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-ellipsis-v"></i> </button> <div class="dropdown-menu action-column-dropdown-menu" aria-labelledby="dropdownMenuButton"> ' . $operate . ' </div> </div>';
    }

    /**
     * @param $url
     * @return string
     */
    public static function downloadButton($urls)
    {
        $customClass = ["related-data-form", "btn-inverse-primary"];
        $customAttributes = [
            "title" => trans("database_download"),
        ];
        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12 2V8L14 6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 8L10 6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M7 12C3 12 3 13.79 3 16V17C3 19.76 3 22 8 22H16C20 22 21 19.76 21 17V16C21 13.79 21 12 17 12C16 12 15.72 12.21 15.2 12.6L14.18 13.68C13 14.94 11 14.94 9.81 13.68L8.8 12.6C8.28 12.21 8 12 7 12Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5 12V8.00004C5 5.99004 5 4.33004 8 4.04004" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19 12V8.00004C19 5.99004 19 4.33004 16 4.04004" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
';
        return self::download_urls($iconClass, $urls, $customClass, $customAttributes);
    }

    public static function download_urls(string $iconClass, array $urls, array $customClass = [], array $customAttributes = [])
    {

        $customClassStr = implode(" ", $customClass);
        $class = self::$defaultClasses . ' ' . $customClassStr;
        $attributes = '';
        if (count($customAttributes) > 0) {
            foreach ($customAttributes as $key => $value) {
                $attributes .= $key . '="' . $value . '" ';
            }
        }

        return '<a href="' . $urls[0] . '" class="' . $class . '" ' . $attributes . ' ><i class="' . $iconClass . '"></i></a>  <a href="' . $urls[1] . '" class="' . $class . '" ' . $attributes . ' ><i class="fa fa-image"></i></a>  ';
    }

    // View Button
    public static function viewButton($url, $customClass = [], $customAttributes = [])
    {
        $iconClass = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.5799 11.9999C15.5799 13.9799 13.9799 15.5799 11.9999 15.5799C10.0199 15.5799 8.41992 13.9799 8.41992 11.9999C8.41992 10.0199 10.0199 8.41992 11.9999 8.41992C13.9799 8.41992 15.5799 10.0199 15.5799 11.9999Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        return self::button(
            $iconClass,
            $url,
            array_merge(["btn-eye"], $customClass),
            array_merge(["title" => trans("View")], $customAttributes)
        );
    }
}
