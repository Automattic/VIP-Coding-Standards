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