<!-- HTML Document -->

<div class="appui-database-data-binary">
  <bbn-button :notext="true"
              icon="nf nf-md-content_copy"
              label="_('Copy uid')"
              @click="copy"/>
  <bbn-button bbn-if="isConstraint"
              :notext="true"
              icon="nf nf-fa-eye"
              label="_('See referenced row')"
              @click="goto"/>
  <span bbn-if="isForeignKey"
        bbn-text="displayValue"/>
</div>
