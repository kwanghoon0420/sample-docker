<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointResource\Pages;
use App\Filament\Resources\PointResource\RelationManagers;
use App\Models\Point;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PointResource extends Resource
{
    protected static ?string $model = Point::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. ID 표시
                TextColumn::make('id')->sortable(),

                TextColumn::make('user_id')
                    ->label('user_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능

                // 3. 포인트 금액 (bcmath 연산 결과) - 콤마(,) 표시
                TextColumn::make('remain_amount')
                    ->label('remain_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),

                TextColumn::make('status')
                    ->label('status')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),

                // 4. 생성일
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoints::route('/'),
            'create' => Pages\CreatePoint::route('/create'),
            'edit' => Pages\EditPoint::route('/{record}/edit'),
        ];
    }
}
