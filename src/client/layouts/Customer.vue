<template>
  <main id="main" class="bg-black text-white flex lg:flex-row">
    <CustomerSideBar />
    <div class="h-screen">
      <div class="w-full mt-4">
        <nuxt />
      </div>
    </div>
  </main>
</template>

<script>
import CustomerSideBar from './Customer/CustomerSideBar'

export default {
  components: {
    CustomerSideBar,
  },
}
</script>

<style lang="scss" scoped>
#main {
  display: grid;
  grid-template-columns: 15% 85%;
}
</style>
