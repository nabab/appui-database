<!-- HTML Document -->

<div class="appui-database-roweditorselector">
  <bbn-dropdown :source="source.project.path"
                source-value="id"
                v-model="currentPathId"
                :storage="true"
                :storage-full-name="'appui-newide-path-dd-' + source.project.id"
                :disabled="isDropdownPathDisabled"/>
  <!--bbn-tree :source="root + 'data/tree'"
            :map="mapTree"
            :data="{
                   id_project: source.project.id,
                   type: currentTypeCode,
                   id_path: currentPathId
                   }"
            :storage="true"
            :storage-full-name="'appui-newide-type-th-' + source.project.id + '-' + currentRoot"
            ref="tree"
            @nodeDblclick="treeNodeActivate"
            :icon-color="iconColor"
            :draggable="true"
            @move="moveNode"
            :menu="treeMenu"/-->
</div>