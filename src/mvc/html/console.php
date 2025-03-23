<!-- HTML Document -->

<div class="bbn-w-100">
  <template v-for="item in codes">
    <component :is="$options.components.request" v-model="item.code"/>
  </template>
  <request v-model="code"
           :databases="source.databases"
           :database="source.database"
           mode="write"/>
</div>


