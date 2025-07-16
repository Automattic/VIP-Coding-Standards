<?php

?>
<script>
{% autoescape false %}
    Everything will be outputted as is in this block
{% endautoescape %}

{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>

<?php

echo '<script>
{% autoescape false %}
    Everything will be outputted as is in this block
{% endautoescape %}

{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>';

echo "<script>
{% autoescape false %}
    Everything will be $outputted as is in this {${$object->getBlock( SOME_FLAG | raw )}}
{% endautoescape %}

{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>
";

echo <<<'EOD'
<script>
{% autoescape false %}
    Everything will be outputted as is in this block
{% endautoescape %}

{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>
EOD;

echo <<<EOD
<script>
{% autoescape false %}
    Everything will be $outputted as is in this {$obj->blocks[SOME_FLAG | raw]->name}
{% endautoescape %}
EOD;
echo <<<"EOD"
{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>
EOD;
