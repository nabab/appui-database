<!-- HTML Document -->

<div class="appui-database-itemviewerselector bbn-padding">
  <bbn-loader v-if="!ready">
  </bbn-loader>
  <div class="bbn-w-100" v-if="ready">
    <div class="bbn-w-100">
      <bbn-dropdown :source="project.path"
                    v-if="ready"
                    source-value="id"
                    v-model="currentPathId"
                    :disabled="false"/>
    </div>
    <div class="bbn-w-100">
      <bbn-tree :source="root + 'data/tree'"
                :data="dataTree"
                ref="tree"
                @select="select"
                :scrollable="false"/>
    </div>
  </div>
</div>