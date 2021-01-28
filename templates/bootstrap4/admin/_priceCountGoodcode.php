<div class="form-group row">
    <label for="goodcode" class="col-sm-2 col-form-label text-right">Код товара</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" id="goodcode" name="goodcode" aria-describedby="goodcodeHelp" placeholder="код товара" value="<?=$this->moduleData->material->params['goodcode']?>">
    </div>
    <label for="price" class="col-sm-2 col-form-label text-right">Стоимость</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" id="price" name="price" aria-describedby="priceHelp" placeholder="Стоимость" value="<?=$this->moduleData->material->params['price']?>">
    </div>
    <label for="material_count" class="col-sm-2 col-form-label text-right">Кол-во</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" id="material_count" name="material_count" aria-describedby="material_countHelp" placeholder="Кол-во" value="<?=$this->moduleData->material->params['material_count']?>">
    </div>
</div>