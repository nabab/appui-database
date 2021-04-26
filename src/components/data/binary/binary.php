<!-- HTML Document -->

<div class="appui-database-data-binary">
  <bbn-button :notext="true"
              icon="nf nf-mdi-content_copy"
              text="_('Copy uid')"
              @click="copy"/>
  <bbn-button v-if="isConstraint"
              :notext="true"
              icon="nf nf-fa-eye"
              text="_('See referenced row')"
              @click="goto"/>
</div>