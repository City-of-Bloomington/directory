<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->people
 */
$count = count($this->people);
$heading = $this->title ? "<h1>{$this->title} ($count)</h1>" : '';

if ($count) {
    echo "
    <section class=\"sync-list\">
        $heading
        <div class=\"container\">
    ";
        foreach ($this->people as $p) {
            $title = self::escape($p['title']);
            $name  = self::escape($p['name']);
            if (isset($p['username'])) {
                $url   = BASE_URI.'/people/view?username='.$p['username'];
                $name = "<a href=\"$url\">$name</a>";
            }

            $employeeNum = isset($p['employeeNum']) ? $p['employeeNum'] : '';
            $department  = isset($p['department' ]) ? $p['department' ] : '';
            $username    = isset($p['username'   ]) ? $p['username'   ] : '';

            echo "
            <article>
                <header>$name</header>
                <div>$title</div>
                <div>$department</div>
                <div>$employeeNum</div>
                <div>$username</div>
            </article>
            ";
        }
    echo "
        </div>
    </section>
    ";
}
