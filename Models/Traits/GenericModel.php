<?php

namespace Models\Traits;

trait GenericModel
// trait
// いくつかのメソッド群を異なるクラス階層にある独立したクラスで再利用できるようにします
// PHP のような単一継承言語でコードを再利用するための仕組みのひとつ
{
  public function toArray(): array
  {
    return (array) $this;
  }

  public function toString(): string
  {
    return json_encode($this, JSON_PRETTY_PRINT);
  }
}
