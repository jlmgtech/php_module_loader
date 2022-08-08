<script>
	export let name;
</script>

<main>
	<h1>Hello {name}!</h1>
	<p>Svelte example</p>
    <div> This page shows off the ability of the router to render single-page applications. </div>
    <div> The configuration is simple: </div>
    <div class='code-holder'>
        <pre>
Router::assets("/cp/router/", __DIR__ . "/public/");           # serve static files
Router::single("/cp/router/", __DIR__ . "/public/index.html"); # handle routes
        </pre>
    </div>
    <div>
        The heuristic that prevents routing collisions is that paths ending
        with a file extension are routed as static assets, and all other paths are
        served index.html.
    </div>
    <div>
        The advantage to this approach is that you still get 404s for missing
        files, unlike other solutions that will serve index.html for all
        requests that don't match static routes.
    </div>
</main>

<style>
	main {
		text-align: center;
		padding: 1em;
		max-width: 240px;
		margin: 0 auto;
	}

	h1 {
		color: #ff3e00;
		text-transform: uppercase;
		font-size: 4em;
		font-weight: 100;
	}

    .code-holder {
        background-color: #f5f5f5;
        border-radius: 5px;
        margin: 1em auto;
        padding: 1em;
        width: 50%;
    }

    .code-holder pre {
        text-align: left;
    }

	@media (min-width: 640px) {
		main {
			max-width: none;
		}
	}
</style>
