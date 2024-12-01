<!-- HTML Document -->

<bbn-splitter orientation="vertical" v-if="is_data">
  <bbn-pane size="10%" :resizable="true">
    <div class="bbn-l bbn-padding bbn-l bbn-grid-fields">
      <span>Query: </span>
      <span v-text="request"></span>

      <span v-if="num && (type === 'read')">Number of row(s) resulting from the request: </span>
      <span v-if="num && (type === 'read')" v-text="num"></span>
    </div>
		
  </bbn-pane>
  <bbn-pane v-if="(type === 'write')">
    <div class="bbn-vlpadding">
      <span v-text="(num > 0) ? ( 'Number of row(s) affected from the request ' + num ) : ( 'No rows affected from the request' )">

      </span>
    </div>
  </bbn-pane>

  <bbn-pane v-if="results && (type === 'read') ">
    <bbn-table v-if="isArray(results) && ( results.length > 1 ) && ( num > 1) "
               :source="results"
               :columns="columns"
               >
    </bbn-table>

    <div v-if="isArray(results) && ( num === 1 ) && ( results.length === 1 )" class="bbn-vlpadding">
      <span v-for="(r, i) in results[0]" v-html="i + ' : ' + r + '<br><br>' "></span>
    </div>

  </bbn-pane>
</bbn-splitter>
<div v-else>
  No query
</div>