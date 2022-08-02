<template>
  <iframe
    id="inlineFrameExample"
    class="game"
    title="Inline Frame Example"
    width="100%"
    height="100vh"
    src="https://game.test.red6six.com/"
  >
  </iframe>
</template>

<script>
export default {
  head: {
    bodyAttrs: {
      class: 'game-page',
    },
  },
}
</script>

<style>
.game-page {
  position: fixed;
  width: 100%;
  height: 100%;
}

.game {
  height: 100vh;
  padding-top: 12px;
}
</style>
