<script setup>
import { ref } from 'vue';
import authApiRepository from "@/composables/authApiRepository";
import { mdiForwardburger, mdiBackburger, mdiMenu } from '@mdi/js';
import { useRouter } from 'vue-router';
import menuAside from '@/menuAside.js';
import menuNavBar from '@/menuNavBar.js';
// import { useDarkModeStore } from '@/stores/darkMode.js'
import BaseIcon from '@/components/BaseIcon.vue';
import NavBar from '@/components/NavBar.vue';
import NavBarItemPlain from '@/components/NavBarItemPlain.vue';
import AsideMenu from '@/components/AsideMenu.vue';
import FooterBar from '@/components/FooterBar.vue';

const layoutAsidePadding = 'xl:pl-60'

// const darkModeStore = useDarkModeStore()
const { logout } = authApiRepository();
const router = useRouter()

const isAsideMobileExpanded = ref(false)
const isAsideLgActive = ref(false)

router.beforeEach(() => {
  isAsideMobileExpanded.value = false
  isAsideLgActive.value = false
})

const menuClick = (event, item) => {
  if (item.isToggleLightDark) {
    // darkModeStore.set()
  }

  if (item.isLogout) {
    logout().then((response) => {
      if(response.status === 200){
        router.push({name: 'login'});
      }
    });
  }
}
</script>

<template>
  <div
    :class="{
      'overflow-hidden lg:overflow-visible': isAsideMobileExpanded
    }"
  >
    <div
      :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="w-screen min-h-screen pt-14 transition-position lg:w-auto slate-800 text-slate-100"
    >
      <NavBar
        :menu="menuNavBar"
        :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
        @menu-click="menuClick"
      >
        <NavBarItemPlain
          display="flex lg:hidden"
          @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded"
        >
          <BaseIcon :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain display="hidden lg:flex xl:hidden" @click.prevent="isAsideLgActive = true">
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain use-margin>
          <!-- <FormControl placeholder="Search (ctrl+k)" ctrl-k-focus transparent borderless /> -->
        </NavBarItemPlain>
      </NavBar>
      <AsideMenu
        :is-aside-mobile-expanded="isAsideMobileExpanded"
        :is-aside-lg-active="isAsideLgActive"
        :menu="menuAside"
        @menu-click="menuClick"
        @aside-lg-close-click="isAsideLgActive = false"
      />
      <slot />
      <FooterBar>
        <!-- Content -->
      </FooterBar>
    </div>
  </div>
</template>
