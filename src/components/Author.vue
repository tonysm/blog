<template>
	<div class="author">

		<g-image alt="Author image" class="author__image" src="~/assets/images/author.jpg" width="180" height="180" blur="5" />

		<h1 v-if="showTitle" class="author__site-title">
			{{ $static.metaData.siteName }}
		</h1>

		<p class="author__intro">
            My name is <a href="//twitter.com/tony0x01">Tony Messias</a>. I'm a <a href="//madewithlove.be" title="I work here. Looking for a job? Check us out.">Software Engineer</a>. This is my personal blog where I share thoughts and experiments on programming.
		</p>

		<p class="author__links">
			<a href="//github.com/tonysm" title="This is my GitHub - where I share experimental repositories. You can check it out.">GitHub</a>
			<a href="//www.meetup.com/pt-BR/maceio-dev-meetup/" title="I co-organize a meetup in my Maceió/AL (Brazil), if you are nearby, come join us!">Maceió DEV Meetup</a>
			<a href="//www.youtube.com/channel/UCGtfJjAR5JeBPAmxN_ZHx4Q?view_as=subscriber" title="I often share screencasts on YouTube">Screencasts</a>
		</p>

	</div>
</template>

<static-query>
query {
  metaData {
    siteName
  }
}
</static-query>

<script>
export default {
	props: ['showTitle']
}
</script>

<style lang="scss">
.author {
	margin: 0 auto;
	max-width: 500px;
	text-align: center;
	padding: calc(var(--space) / 2) 0;

	&__image {
		border-radius: 100%;
		width: 90px;
		height: 90px;
		margin-bottom: 1em;
	}

	&__intro {
		opacity: .8;
	}

	&__site-title {
		font-size: 1.5em;
	}

	&__links {
		margin-top: -.5em;
		a {
			margin: 0 .5em;
		}
	}
}
</style>
