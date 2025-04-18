<?php

namespace App\Http\Controllers;

abstract class Controller {

    protected function store_notify( $model ){
        if( $model->isClean() ){
            session()->flash(
                'flash', [
                    'success' => TRUE,
                    'message' => "Registro creado correctamente",
                    'id' => $model->id,
                ]
            );
        } else {
            session()->flash(
                'flash', [
                    'success' => FALSE,
                    'message' => "No se creo el registro",
                ]
            );
        }
    }

    protected function update_notify( $model ){
        if( $model->isClean() ){
            session()->flash(
                'flash', [
                    'success' => TRUE,
                    'message' => "Registro actualizado correctamente",
                    'id' => $model->id,
                ]
            );
        } else {
            session()->flash(
                'flash', [
                    'success' => FALSE,
                    'message' => "No se actualizó el registro",
                ]
            );
        }
    }

}
