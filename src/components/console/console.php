<!-- HTML Document -->

<div class="bbn-overlay bbn-flex-height">
  <div class="bbn-w-100">
    <bbn-button @click="exec">
    </bbn-button>
  </div>
  <div class="bbn-flex-fill">
    <bbn-code v-model="code" mode="sql"/>
  </div>
</div>