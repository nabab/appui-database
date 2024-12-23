<!-- HTML Document -->

<div class="appui-database-data-binary">
  <bbn-button :notext="true"
              icon="nf nf-mdi-content_copy"
              text="_('Copy uid')"
              @click="copy"/>
  <bbn-button bbn-if="isConstraint"
              :notext="true"
              icon="nf nf-fa-eye"
              text="_('See referenced row')"
              @click="goto"/>
  <span bbn-if="isForeignKey"
        bbn-text="displayValue"/>
</div>
