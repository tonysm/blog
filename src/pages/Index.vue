<template>
  <Layout :show-logo="false">
    <!-- Author intro -->
    <Author :show-title="true" />

    <!-- List posts -->
    <div class="posts">
      <PostCard v-for="edge in $page.posts.edges" :key="edge.node.id" :post="edge.node"/>
    </div>

  </Layout>
</template>

<page-query>
{
  posts: allPost(
    filter: { published: { eq: true }},
    sortBy: "date",
    order: DESC,
    perPage: 99999
  ) {
    edges {
      node {
        id
        title
        path
        tags {
          id
          title
          path
        }
        date (format: "DD.MM.YYYY")
        coverImage (width: 770, height: 380, blur: 10)
        timeToRead
        ...on Post {
            id
            title
            path
        }
      }
    }
  }
}
</page-query>

<script>
import Author from '~/components/Author.vue'
import PostCard from '~/components/PostCard.vue'

export default {
  components: {
    Author,
    PostCard
  },
  metaInfo: {
    title: 'Home'
  }
}
</script>
