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
        $fields = ['title', 'firstname', 'lastname', 'department', 'username', 'employeeNum'];
        foreach ($this->people as $p) {
            foreach ($fields as $f) { $$f = isset($p[$f]) ? self::escape($p[$f]) : ''; }

            $name = "$firstname $lastname";
            if ($username) {
                $url   = BASE_URI."/people/view?username=$username";
                $name = "<a href=\"$url\">$name</a>";
            }

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
