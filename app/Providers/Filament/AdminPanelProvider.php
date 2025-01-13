<?php

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('人事管理系統')
            ->navigationGroups([
                '系統管理',
                '人事管理',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->locale('zh_TW')
            ->translations([
                'zh_TW' => [
                    'filament::resources.pages.list.title' => '列表',
                    'filament::resources.pages.create.title' => '新增',
                    'filament::resources.pages.edit.title' => '編輯',
                    'filament::resources.buttons.create.label' => '新增',
                    'filament::resources.buttons.edit.label' => '編輯',
                    'filament::resources.buttons.save.label' => '儲存',
                    'filament::resources.buttons.cancel.label' => '取消',
                    'filament::resources.buttons.delete.label' => '刪除',
                ],
            ]);
    }
}
