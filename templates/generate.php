<div class="container" style="max-width: 1600px">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                Image generation has started
            </div>
        </div>
    </div>
</div>

<script>
    class Generate
    {
        async startGenerating() {
            const url = "/generate";
            const response = await fetch(url);
        }
    }
    const generate = new Generate();
    generate.startGenerating();
</script>