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
        columns: this.getColumnsCfg()
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
      getColumnsCfg() {
        let columnsStructure = this.source.structure;
        bbn.fn.log("columnStructure");
        let res = [];
        for (const [key, value] of Object.entries(columnsStructure.fields)) {
          let column = {
            "field": key,
            "text": key,
            "width": value.maxlength
          }
          res.push(column);
        }
        bbn.fn.log('columnStructure:', columnsStructure);
				bbn.fn.log('tablecfg:', this.source.tableCfg);
        bbn.fn.log('res:', res);
        return this.source.tableCfg;
      },
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
