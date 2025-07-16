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
    Everything will be $outputted as is in this block
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
    Everything will be $outputted as is in this block
{% endautoescape %}
EOD;
echo <<<"EOD"
{% autoescape %}
    {{ safe_value|raw }}
{% endautoescape %}
</script>
EOD;
