(() => {
  return {
    data(){
      let r = this.source.tableCfg;
      r.push({
        title: bbn._('Actions'),
        fixed: 'right',
        buttons: [{
          notext: true,
          icon: 'nf nf-fa-edit',
          action: 'edit'
        }, {
          notext: true,
          icon: 'nf nf-fa-times',
          action: 'delete'
        }],
        width: '100'
      });
      return {
        root: appui.plugins['appui-database'] + '/',
        columns: r
      };
      /*let cols = [];
      if ( this.source.columns.length ){
        bbn.fn.each(this.source.columns, (c) => {
          if ( cols.length > 5 ){
            c.hidden = true;
          }
          cols.push(c);
        })
      }
      return {
        //columns: cols;
      }*/

    },
    computed: {
    },
    methods:{
      see(row, col){
        if (this.source.constraints[col.field] && row[col.field]) {
          this.post(
            this.root + 'actions/show',
            bbn.fn.extend({id: row[col.field]}, this.source.constraints[col.field]),
            () => {
              bbn.fn.log("SEE TO DO!")
            }
          )
        }
      },
      edit(row, obj, idx){
        this.$refs.table.edit(bbn.fn.extend(row, {original: this.$refs.table.originalData[idx]}), bbn._('Edit data'), idx);
      },
      success(d){
        if (d.success) {
          appui.success(bbn._('Data successfully updated'))
        }
      },
      copy(row, col, idx){
        if (row[col.field]) {
          bbn.fn.copy(row[col.field]);
          appui.success(bbn._('UID copied'));
        }
        else {
          appui.error(bbn._('The field has value ' + (row[col.field] !== '') ? row[col.field] : '""'))
        }
      },
    }
  }
})();
