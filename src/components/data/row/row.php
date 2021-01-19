<!-- HTML Document -->
<div class="bbn-grid-fields bbn-padded">
  <template v-for="d in data">
    <div v-text="d.title"></div>
    <div v-text="d.value"></div>
  </template>
</div>