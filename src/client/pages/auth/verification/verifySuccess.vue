<template>
  <div class="container mx-auto pt-24 pb-12 px-6 bg-black flex flex-col items-center justify-center text-white">
    <div class="w-80 max-w-xs m-auto mb-12">
      <img src="~/assets/svg/logo-strap.svg" alt="Red6Six logo" />
    </div>
    <h3 class="mb-8">Your email has been verified successfully. Please Log In to let the game begin..</h3>
    <div
      class="
        block
        bg-gold
        hover:bg-brand
        text-brand
        hover:text-red
        font-bold
        py-2
        px-4
        rounded
        max-w-xs
        mx-auto
        focus:outline-none focus:shadow-outline
        cursor-pointer
      "
    >
      <nuxt-link to="/login">Login</nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VerifySuccess',
}
</script>

<style lang="scss" scoped>
h3 {
  font-size: 1.17em;
  font-weight: 600;
}
.w-80 {
  width: 80%;
}
</style>
