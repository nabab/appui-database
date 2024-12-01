<!-- HTML Document -->
<div class="bbn-grid-fields bbn-padding">
  <template v-for="d in data">
    <div v-text="d.title"></div>
    <div v-text="d.value"></div>
  </template>
</div>