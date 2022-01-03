<?php

namespace Unakit;

class Accessible_Walker_Nav_Menu extends \Walker_Nav_Menu
{

    /**
     * Unique, incremented id to reference each parent <p> sub-menu relation.
     */
    private $submenu_id = 0;

    /**
     * Converts an array of attributes to valid html.
     *
     * @param  array $atts
     * @return string html output
     */
    function get_html_atts($atts)
    {
        $html_output = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value       = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $html_output .= ' ' . $attr . '="' . $value . '"';
            }
        }
        return $html_output;
    }

    /**
     * Converts and echoes an array of attributes as escaped valid html.
     *
     * @param  array $atts
     * @return void
     */
    function html_atts($atts)
    {
        echo esc_html($this->get_html_atts($atts));
    }

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Default class.
        $classes = ['submenu'];

        $id = 'submenu-' . ($this->submenu_id - 1);

        /** This filter is documented in wp-includes/class-walker-nav-menu.php */
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "$n$indent<ul id='$id'$class_names data-depth='$depth'>$n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $item_output = '';
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
        } else {
            $t = "\t";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $has_link = (strlen($item->url) > 0) && ($item->url !== "#");
        $has_children = $args->walker->has_children;

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /** This filter is documented in wp-includes/class-walker-nav-menu.php */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);
        /** This filter is documented in wp-includes/class-walker-nav-menu.php */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $menu_parent_id = 'submenu-' . $item->id;

        $li_atts = [];
        $li_atts["id"] = esc_attr($id);
        $li_atts["class"] = $class_names;

        $link_atts['title']  = $item->attr_title;
        $link_atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $link_atts['rel'] = 'noopener noreferrer';
        } else {
            $link_atts['rel'] = $item->xfn;
        }
        $link_atts['href']         = !empty($item->url) ? $item->url : '';
        $link_atts['aria-current'] = $item->current ? 'page' : '';

        /** This filter is documented in wp-includes/class-walker-nav-menu.php */
        $link_atts = apply_filters('nav_menu_link_attributes', $link_atts, $item, $args, $depth);

        $li_attributes = $this->get_html_atts($li_atts);
        $link_attributes = $this->get_html_atts($link_atts);

        $item_output .= sprintf(
            "%s<li %s>",
            $indent,
            $li_attributes
        );

        $link_output = sprintf(
            "%s<a %s>%s</a>%s",
            $args->before,
            $link_attributes,
            $args->link_before . $title . $args->link_after,
            $args->after
        );

        if (!$has_children) {
            $item_output .= $link_output;
        } else {
            $input_id = esc_attr('toggle-submenu-' . $this->submenu_id);
            $dropdown_icon = "<span class='submenu-toggle__icon' aria-hidden='true'>&#x25be;</span>";
            if ($has_link) {
                $item_output .= $link_output;
                $item_output .= sprintf(
                    '<input
                        id="%1$s"
                        type="checkbox"
                        class="submenu-toggle__checkbox submenu-toggle__checkbox--button"
                        name="%1$s" value="%2$s"
                        autocomplete="off"
                        aria-label="%2$s"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-owns="%3$s"
                    ><label
                        for="%1$s"
                        class="submenu-toggle__label submenu-toggle__label--button"
                        aria-hidden="true">
                            %4$s
                    </label>',
                    $input_id,
                    esc_attr(sprintf(
                        // translators: %1$s: Title of the submenus parent item
                        __('Toggle %1$s submenu', 'unakit'),
                        $title
                    )),
                    esc_attr('submenu-' . $this->submenu_id),
                    $dropdown_icon
                );
            } else {
                $item_output .= sprintf(
                    '<input
                        id="%1$s"
                        type="checkbox"
                        class="submenu-toggle__checkbox submenu-toggle__checkbox--full"
                        name="%1$s" value="%2$s"
                        autocomplete="off"
                        aria-label="%2$s"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-owns="%3$s"
                    ><label
                        for="%1$s"
                        class="submenu-toggle__label
                        submenu-toggle__label--full"
                        aria-hidden="true">
                            %4$s
                    </label>',
                    $input_id,
                    esc_attr(sprintf(
                        // translators: %1$s: Title of the submenus parent item
                        __('Toggle %1$s submenu', 'unakit'),
                        $title
                    )),
                    esc_attr('submenu-' . $this->submenu_id),
                    "<span class='submenu-toggle__label__title'>" . esc_html($title) . "</span>" . $dropdown_icon
                );
            }
            $this->submenu_id++;
        }

        /** This filter is documented in wp-includes/class-walker-nav-menu.php */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
