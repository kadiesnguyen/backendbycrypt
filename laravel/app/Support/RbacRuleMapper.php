<?php

namespace App\Support;

class RbacRuleMapper
{
    public static function ruleNameToSlug(string $ruleName): string
    {
        return strtolower(str_replace('/', '.', $ruleName));
    }

    public static function slugToRuleName(string $slug): string
    {
        $parts = explode('.', $slug);
        if (count($parts) < 3) {
            return strtolower($slug);
        }

        return ucfirst($parts[0]) . '/' . ucfirst($parts[1]) . '/' . $parts[2];
    }

    public static function normalizeRule(string $rule): string
    {
        $rule = strtolower(trim($rule));

        if (str_contains($rule, '/')) {
            return $rule;
        }

        return str_replace('.', '/', $rule);
    }
}
